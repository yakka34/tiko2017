<template>
    <textarea class="form-control"></textarea>
</template>

<script>
    export default {

        mounted() {
            let vm = this;
            window.tinymce.init({
                selector: '#'+vm.$el.id,
                plugins: 'image',
                invalid_elements: 'script',
                init_instance_callback: function(editor) {
                    editor.on('blur', function(ed) {
                        console.log("content: " + editor.getContent());
                        vm.$emit('content-updated', editor.getContent());
                    });
                }
            });
        }
    }
</script>