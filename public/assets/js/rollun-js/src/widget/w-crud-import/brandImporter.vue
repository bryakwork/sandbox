<template>
    <div id="w-crud-import">
        <button class="btn btn-default btn-md" id="show-modal" @click="showModal = true">{{label}}</button>
        <paste-modal v-if="showPasteModal" @close="showPasteModal = false"
                     v-bind:importfields="importfields"
                     v-bind:noid="noid"
                     v-bind:inputseparator="inputseparator"
                     v-bind:placeholder="placeholder"
                     v-bind:popuptitle="popuptitle">
        </paste-modal>
        <button class="btn btn-default btn-md" id="show-file2ds" @click="showFile2DS = true">{{label+" (csv)"}}</button>
        <w-crud-file2ds v-if="showFile2DS" v-on:close="showFile2DS = false"></w-crud-file2ds>
    </div>
</template>

<script>
    import widget from '../../widget'
    import pasteModal from './modal.vue'

    export default {
        name: 'w-crud-import',
        mixins: [widget],
        components: {'paste-modal': pasteModal},
        props: [
            'label',
            'importfields',
            'noid',
            'inputseparator',
            'placeholder',
            'popuptitle',
            'formname',
            'uploadurl',
            'uploadaccept',
            'uploadmultiple',
            'uploadheaders'
        ],
        provide: function () {
            return {
                formName: this.formname,
                action: this.uploadurl,
                accept: this.uploadaccept,
                multiple: this.uploadmultiple,
                headers: this.uploadheaders
            }
        },
        data: function () {
            return {
                showPasteModal: false,
                showFile2DS: false
            }
        },
    }
</script>