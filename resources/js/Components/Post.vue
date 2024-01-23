<script setup>
const props = defineProps({
    post: Object
})
</script>
<template>
    <div class="flex flex-col space-y-4 items-center">
        <h1 class="text-5xl font-bold w-full">{{ post.title }}</h1>
        <div class="flex items-center space-x-2 w-full">
            <div class="p-5 aspect-square bg-gray-300 rounded-full"></div>
            <div class="flex flex-col">
                <span>{{ post.author.name }}</span>
                <span class="text-gray-500">{{ post.published_at }}</span>
            </div>
        </div>
        <div class="w-full">
            <div class="rounded-lg bg-gray-300 aspect-video w-full">&nbsp;</div>
            <span class="text-sm text-gray-500">Source: The Ball Street Kernel (Optional)</span>
        </div>
        <div class="relative overflow-y-hidden h-32">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-100">

            </div>
            <p>{{ post.content }}</p>
            <p>Curabitur sit amet bibendum est, et interdum tortor. Mauris vestibulum lectus in mauris fermentum facilisis. Sed egestas, odio sodales elementum faucibus, neque lacus pellentesque tellus, eget elementum magna lectus vel nisl. Donec commodo, ipsum non cursus tincidunt, dui lorem pretium odio, sed condimentum leo dolor ut erat. Donec malesuada at urna et volutpat. Nulla mauris urna, feugiat eget auctor eget, consequat nec lacus. Praesent vel quam augue. Pellentesque id tempor erat. Proin interdum ultrices ligula, et fringilla urna sollicitudin id. Donec elit neque, rutrum id metus nec, vestibulum ullamcorper mauris.</p>
            <p>Curabitur ac dolor eu magna vestibulum egestas ut in erat. Aliquam suscipit ante mauris, vel malesuada nibh euismod in. Vivamus eu sagittis lorem. Suspendisse volutpat ante pharetra dui semper tristique. Praesent eu vulputate nisl. Duis finibus turpis eu magna malesuada aliquam. Aenean eu est velit. Ut eu dolor sed elit porta sodales at a dui. Vivamus feugiat varius interdum.</p>
            <p>Nunc nunc lacus, lacinia id viverra condimentum, dictum non dolor. Sed maximus quis dolor vel faucibus. Pellentesque tincidunt tortor a enim lobortis, convallis pharetra urna tincidunt. Morbi vulputate, erat sit amet porta dignissim, dolor ex maximus augue, a placerat libero mi dapibus nulla. Mauris gravida ultrices dui, et faucibus urna rutrum ut. Vestibulum eget massa eu orci ornare cursus. Nam sapien nisi, mattis vel quam a, laoreet molestie sem. Suspendisse in convallis ante, nec ullamcorper augue. Morbi ac dui consequat, lobortis enim at, suscipit orci. Donec vel massa ut odio tincidunt efficitur. Curabitur maximus turpis vel ipsum volutpat fringilla.</p>
        </div>
        <a href="#" class="rounded-md bg-gray-200 px-4 py-1">Keep Reading</a>
    </div>
</template>
