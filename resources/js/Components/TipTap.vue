<script setup>
import {EditorContent, useEditor,} from '@tiptap/vue-3'
import {ref, watch} from "vue";
import {StarterKit} from "@tiptap/starter-kit";

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    editable: {
        type: Boolean,
        default: true,
    }
})

const source = ref(false);

const editable = ref(props.editable);
watch(editable, (old, current) => {
    editor.value.setEditable(current);
})

const emit = defineEmits(['update:modelValue'])
const extensions = [
    StarterKit,
    Image,
];
const editor = useEditor({
    extensions: extensions,
    content: props.modelValue,
    onUpdate: () => {
        emit('update:modelValue', editor.value.getHTML())
    },
    onCreate: () => {
        editor.value.setEditable(editable.value);
    }
})

watch(props.modelvalue, () => {
    editor.value.commands.setContent(props.modelValue, false)
}, {
    deep: true,
});
</script>
<template>
    <div class="y items-center">
        <div v-if="editor && editable" class="x w-full flex-wrap">
            <div class="flex flex-wrap items-center text-xl p-1 gap-1 text-neutral-800">
                <button title="Bold" class="px-2 py-0.5 rounded" @click="editor.chain().focus().toggleBold().run()" :class="{ 'bg-neutral-100/50': editor.isActive('bold') }">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M6.8 19V5h5.525q1.625 0 3 1T16.7 8.775q0 1.275-.575 1.963t-1.075.987q.625.275 1.388 1.025T17.2 15q0 2.225-1.625 3.113t-3.05.887zm3.025-2.8h2.6q1.2 0 1.463-.612t.262-.888q0-.275-.262-.887T12.35 13.2H9.825zm0-5.7h2.325q.825 0 1.2-.425t.375-.95q0-.6-.425-.975t-1.1-.375H9.825z"/></svg>
                </button>
                <button title="Italics" class="px-2 py-0.5 rounded" @click="editor.chain().focus().toggleItalic().run()" :class="{ 'bg-neutral-100/50': editor.isActive('italic') }">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M6.25 19q-.525 0-.888-.363T5 17.75q0-.525.363-.888t.887-.362H9l3-9H9.25q-.525 0-.888-.363T8 6.25q0-.525.363-.888T9.25 5h7.5q.525 0 .888.363T18 6.25q0 .525-.363.888t-.887.362H14.5l-3 9h2.25q.525 0 .888.363t.362.887q0 .525-.363.888T13.75 19z"/></svg>
                </button>
                <button title="Strikethrough" class="relative px-2 py-0.5 rounded" @click="editor.chain().focus().toggleStrike().run()" :class="{ 'bg-neutral-100/50': editor.isActive('strike') }">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M3 14q-.425 0-.712-.288T2 13q0-.425.288-.712T3 12h18q.425 0 .713.288T22 13q0 .425-.288.713T21 14H3Zm7.5-4V7h-4q-.625 0-1.062-.437T5 5.5q0-.625.438-1.062T6.5 4h11q.625 0 1.063.438T19 5.5q0 .625-.437 1.063T17.5 7h-4v3h-3Zm0 6h3v2.5q0 .625-.437 1.063T12 20q-.625 0-1.062-.437T10.5 18.5V16Z"/></svg>
                </button>
                <button v-if="false" title="Underline" class="relative px-2 py-0.5 rounded" @click="editor.chain().focus().toggleUnderline().run()" :class="{ 'bg-neutral-100/50': editor.isActive('underline') }">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M6 21q-.425 0-.712-.288T5 20q0-.425.288-.712T6 19h12q.425 0 .713.288T19 20q0 .425-.288.713T18 21zm6-4q-2.525 0-3.925-1.575t-1.4-4.175V4.275q0-.525.388-.9T7.975 3q.525 0 .9.375t.375.9V11.4q0 1.4.7 2.275t2.05.875q1.35 0 2.05-.875t.7-2.275V4.275q0-.525.388-.9T16.05 3q.525 0 .9.375t.375.9v6.975q0 2.6-1.4 4.175T12 17"/></svg>
                </button>

                <div class="px-2">&bull;</div>

                <button title="Blockquote" class="px-2 py-1 rounded" @click="editor.chain().focus().toggleBlockquote().run()" :class="{ 'bg-neutral-100/50': editor.isActive('blockquote') }">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M6.55 16.5L8 14q-1.65 0-2.825-1.175T4 10q0-1.65 1.175-2.825T8 6q1.65 0 2.825 1.175T12 10q0 .575-.137 1.063T11.45 12l-3.175 5.5q-.125.225-.35.363t-.5.137q-.575 0-.862-.5t-.013-1m9 0L17 14q-1.65 0-2.825-1.175T13 10q0-1.65 1.175-2.825T17 6q1.65 0 2.825 1.175T21 10q0 .575-.137 1.063T20.45 12l-3.175 5.5q-.125.225-.35.363t-.5.137q-.575 0-.862-.5t-.013-1"/></svg>
                </button>

                <div class="px-2">&bull;</div>

                <button title="Undo" class="px-2 py-1 rounded" @click="editor.chain().focus().undo().run()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M8 19q-.425 0-.712-.288T7 18q0-.425.288-.712T8 17h6.1q1.575 0 2.738-1T18 13.5q0-1.5-1.162-2.5T14.1 10H7.8l1.9 1.9q.275.275.275.7t-.275.7q-.275.275-.7.275t-.7-.275L4.7 9.7q-.15-.15-.213-.325T4.426 9q0-.2.063-.375T4.7 8.3l3.6-3.6q.275-.275.7-.275t.7.275q.275.275.275.7t-.275.7L7.8 8h6.3q2.425 0 4.163 1.575T20 13.5q0 2.35-1.737 3.925T14.1 19z"/></svg>
                </button>
                <button title="Redo" class="px-2 py-1 rounded" @click="editor.chain().focus().redo().run()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M16.2 10H9.9q-1.575 0-2.738 1T6 13.5Q6 15 7.163 16T9.9 17H16q.425 0 .713.288T17 18q0 .425-.288.713T16 19H9.9q-2.425 0-4.163-1.575T4 13.5q0-2.35 1.738-3.925T9.9 8h6.3l-1.9-1.9q-.275-.275-.275-.7t.275-.7q.275-.275.7-.275t.7.275l3.6 3.6q.15.15.213.325t.062.375q0 .2-.062.375T19.3 9.7l-3.6 3.6q-.275.275-.7.275t-.7-.275q-.275-.275-.275-.7t.275-.7z"/></svg>
                </button>
            </div>
        </div>
        <editor-content class="w-full prose-ul:list-disc prose-ul:list-inside prose-ol:list-decimal prose-ol:list-inside prose-p:p-1 prose-blockquote:pl-2 prose-blockquote:border-l-2 prose-blockquote:border-l-neutral-600 prose-ui !prose-invert" :class="{'p-3 bg-neutral-100/50 rounded': editable}" :editor="editor" />
        <pre v-if="source" class="p-2 text-xs w-full overflow-x-auto">{{ modelValue }}</pre>
    </div>
</template>
<style>
div[data-youtube-video] > iframe {
    @apply rounded-lg aspect-video h-[20rem]
}

.rounded > .ProseMirror {
    padding: 0.5rem 0.75rem !important;
}
.ProseMirror-focused {
    outline: none;
}

.ProseMirror p.is-editor-empty:first-child::before {
    opacity: 0.5;
    content: 'Write something nice...';
    float: left;
    height: 0;
    pointer-events: none;
}


.ProseMirror li > p {
    display: inline;
}

.ProseMirror li > ol, .ProseMirror li > ul {
    margin-left: 1rem;
}

.ProseMirror a {
    text-decoration: underline;
    cursor: pointer;
}

.ProseMirror ol { list-style-type: decimal;}
.ProseMirror ol ol { list-style-type: lower-alpha;}
.ProseMirror ol ol ol {list-style-type: lower-roman;}

.ProseMirror ul { list-style-type: disc ;}
.ProseMirror ul ul { list-style-type: circle ;}
.ProseMirror ul ul ul {list-style-type: square ;}
</style>
