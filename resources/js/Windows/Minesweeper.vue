<script setup>
import {onMounted, ref, useTemplateRef} from "vue";
import {useTimestamp} from "@vueuse/core";

const canvas = useTemplateRef('canvas');
let ctx = null;
let tileSize = 32;
let tileBorder = 0.1;
let boardSize = ref(10);
let board = [];
let opened = [];
let bombBoard = [];
let flagBoard = [];
let bombs = ref(12);
let mouseX = 0;
let mouseY = 0;

const gameStart = ref(0);

let bombCheat = ref(false);

const S_INIT = 0;
const S_PLAY = 1;
const S_WINS = 2;
const S_LOST = 3;

let state = ref(S_INIT);

let lastMove = null;

const colors = [
    '#FFFFFF',
    '#0100FE',
    '#017F01',
    '#FE0000',
    '#010080',
    '#810102',
    '#008081',
    '#000000',
    '#808080',
];

const xy2Index = (x, y) => {
    return y * boardSize.value + x;
}

const mouse2xy = (e) => {
    let rect = canvas.value.getBoundingClientRect();

    mouseX = Math.floor((e.clientX - rect.left) / tileSize);
    mouseY = Math.floor((e.clientY - rect.top) / tileSize);

    return [mouseX, mouseY];
}

const drawCenterText = (ctx, text, x, y, color = '#000', font = 'bold 20px sans-serif') => {
    ctx.fillStyle = color;
    ctx.font = font;
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';

    ctx.fillText(text, x + tileSize / 2, y + tileSize / 2);
}

const drawTile = (ctx, x, y) => {
    let i = xy2Index(x, y);
    let open = opened[i];
    x *= tileSize;
    y *= tileSize;
    let border = tileSize * tileBorder;

    if (open) {
        border = 1;
        ctx.fillStyle = '#777';
        ctx.fillRect(x, y, tileSize, tileSize);
    } else {
        ctx.fillStyle = '#fff';
        ctx.beginPath();
        ctx.moveTo(x, y);
        ctx.lineTo(x + tileSize, y);
        ctx.lineTo(x, y + tileSize);
        ctx.closePath();
        ctx.fill();

        ctx.fillStyle = '#777';
        ctx.beginPath();
        ctx.moveTo(x + tileSize, y);
        ctx.lineTo(x + tileSize, y + tileSize);
        ctx.lineTo(x, y + tileSize);
        ctx.closePath();
        ctx.fill();
    }

    ctx.fillStyle = '#bbb';
    ctx.fillRect(x + border, y + border, tileSize - border * 2, tileSize - border * 2);

    if (bombBoard[i] && (state.value === S_LOST || state.value === S_WINS || bombCheat.value)) {
        if (state.value === S_LOST) {
            ctx.fillStyle = '#ff000077';
            ctx.fillRect(x, y, tileSize, tileSize);
        }

        if (state.value === S_WINS) {
            drawCenterText(ctx, '😃', x, y, '#000', '20px')
        } else {
            drawCenterText(ctx, '💣', x + 1, y, '#000', '10px')
        }


        if (lastMove === i) drawCenterText(ctx, '❌', x, y + 1, '#000', '16px')
    }

    if (flagBoard[i] && state.value !== S_WINS) {
        drawCenterText(ctx, '🚩', x, y, colors[board[i]], '14px sans-serif')
    }

    if (open && board[i] > 0 && !bombBoard[i]) {
        drawCenterText(ctx, board[i], x, y, colors[board[i]]);
    }
}

const resize = () => {
    let c = canvas.value;

    if (!c) return;

    if (!ctx) ctx = canvas.value.getContext('2d');

    if (c.height !== c.clientHeight || c.width !== c.clientWidth) {
        c.height = c.clientHeight;
        c.width = c.clientWidth;
    }

    tileSize = c.width / boardSize.value;
}

const render = () => {
    let c = canvas.value;

    if (!c || !ctx) return;

    resize();

    ctx.clearRect(0, 0, c.width, c.height);

    for (let i = 0; i < boardSize.value; i++) {
        for (let j = 0; j < boardSize.value; j++) {
            drawTile(ctx, i, j);
        }
    }
}

const clickTile = (index) => {
    if (!opened[index] && !flagBoard[index]) {
        opened[index] = true;
        lastMove = index;

        // first click is never a bomb, we generate bombs here
        if (state.value === S_INIT) {
            let unplacedBombs = bombs.value;
            let others = neighbors(index);
            others.push(index);

            let chance = 0.05;

            for (let i = 0; unplacedBombs > 0; i++) {
                let ii = i % board.length;

                if (!bombBoard[ii] && Math.random() < chance && !others.includes(ii)) {
                    bombBoard[ii] = true;
                    unplacedBombs--;
                }

                if (i > 1200) chance = 0.1;
                if (i > 3000) chance = 1;

                // bug
                if (i > 3000) break;
            }

            for (let i = 0; i < board.length > 0; i++) {
                if (bombBoard[i]) {
                    board[i]++;

                    neighbors(i).forEach(n => {
                        board[n]++;
                    })
                }
            }

            state.value = S_PLAY;
            gameStart.value = Date.now();
        }

        if (board[index] === 0) neighbors(index).forEach(n => {
            // stop clicking as soon as we lose
            if (state.value !== S_LOST) clickTile(n);
        });

        if (bombBoard[index]) {
            state.value = S_LOST;
            gameEndTime.value = Date.now();
        }
    }
}

const click = (e) => {
    if (state.value === S_LOST || state.value === S_WINS) return;

    let index = xy2Index(...mouse2xy(e));

    if (e.button === 0) {
        clickTile(index);
    } else if (e.button === 1) {
        // only clear if there are the proper number of flags
        let expectedFlags = board[index];

        neighbors(index).forEach(n => {
            if (flagBoard[n]) expectedFlags--;
        });

        if (expectedFlags === 0) neighbors(index).forEach(n => clickTile(n));
    } else if (e.button === 2 && !opened[index]) {
        flagBoard[index] = !flagBoard[index];
    }

    let remainingTiles = boardSize.value * boardSize.value;

    opened.forEach(t => {
        if (t) remainingTiles--;
    });

    if (remainingTiles === bombs.value) {
        state.value = S_WINS;
        gameEndTime.value = Date.now();
    }

    render();

    e.preventDefault();
}

const reset = () => {
    state.value = S_INIT;

    resize();

    let tileCount = Math.pow(boardSize.value, 2)

    board = new Array(tileCount).fill(0);
    opened = new Array(tileCount).fill(false);
    bombBoard = new Array(tileCount).fill(false);
    flagBoard = new Array(tileCount).fill(false);

    render();
}

const neighbors = (i, asOffsets = false) => {
    let offsets = [];

    let hasTop = i >= boardSize.value;
    let hasBottom = i < boardSize.value * (boardSize.value - 1);
    let hasLeft = i % boardSize.value !== 0;
    let hasRight = i % boardSize.value !== boardSize.value - 1;

    // top
    if (hasTop) offsets.push(-boardSize.value);
    if (hasBottom) offsets.push(boardSize.value);
    if (hasLeft) offsets.push(-1);
    if (hasRight) offsets.push(1);

    if (hasTop && hasLeft) offsets.push(-boardSize.value - 1);
    if (hasTop && hasRight) offsets.push(-boardSize.value + 1);
    if (hasBottom && hasLeft) offsets.push(boardSize.value - 1);
    if (hasBottom && hasRight) offsets.push(boardSize.value + 1);

    return asOffsets ? offsets : offsets.map(o => i + o);
}

onMounted(() => {
    reset();
})

const timestamp = useTimestamp();

const gameEndTime = ref(0);

const msToClock = (ms) => {
    let s = Math.floor(ms / 1000);
    let m = Math.floor(s / 60);

    return `${m.toString().padStart(2, '0')}:${(s % 60).toString().padStart(2, '0')}`;
}
</script>
<template>
    <div class="flex flex-col space-y-2">
        <p class="text-center font-bold text-2xl" :class="{'!text-red-500': state === S_LOST, '!text-green-500': state === S_WINS}">{{ state === S_INIT ? '00:00' : msToClock((state === S_PLAY ? timestamp : gameEndTime) - gameStart) }}</p>
        <canvas @mouseup="click" @contextmenu.prevent @resize="render" class="aspect-square bg-neutral-900" ref="canvas"></canvas>
        <button @click="reset">Restart</button>

        <span class="text-sm">Bomb Count</span>
        <input type="number" class="!mt-0" :min="1" :max="boardSize * boardSize - 9" v-model.number="bombs" @change="reset"/>

        <span class="text-sm">Board Size</span>
        <input type="number" class="!mt-0" :min="5" :max="20" v-model.number="boardSize" @change="reset"/>

        <label class="x items-center select-none space-x-1.5 !hidden">
            <input type="checkbox" @change="render" v-model="bombCheat"/>
            <span>Cheat</span>
        </label>
    </div>
</template>
