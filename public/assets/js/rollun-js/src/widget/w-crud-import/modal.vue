<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-header">
                        <slot name="header">
                            <h3>{{popuptitle}}</h3>
                        </slot>
                    </div>
                    <div class="modal-body">
                        <slot name="body">
                            <textarea id="editor"
                                      v-model="input"
                                      v-bind:placeholder='placeholder'>
                            </textarea>
                        </slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer">
                            <button class="modal-default-button btn btn-default btn-md" @click="$emit('close')">
                                Отменить
                            </button>
                            <button class="modal-default-button btn btn-primary btn-md" @click="addBrands(input)">
                                Сохранить
                            </button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import mitt from 'mitt';

    export default {
        name: 'paste-modal',
        props: ['importfields', 'noid', 'inputseparator', 'placeholder', 'popuptitle'],
        data: function () {
            return {
                input: '',
            };
        },
        methods: {
            addBrands: function (value) {
                this._writeItems(value);
                this.$emit('close');
                app.$refs.crud.refresh();
            },
            _getTableColumns: function (noid, importfields) {
                importfields = JSON.parse(importfields);
                if (!(importfields === null)) {
                    return importfields;
                }
                noid = JSON.parse(noid);
                var tableColumns = Object.keys(app.$refs.crud.items.schema());
                if (noid === true) {
                    tableColumns.shift()
                }
                return tableColumns;
            },
            _writeItems: function (value) {
                var self = this;
                value = value.split("\n");
                var tableColumns = self._getTableColumns(self.noid, self.importfields);
                value.forEach(function (row) {
                        var items = row.split(self.inputseparator);
                        var result = {};
                        tableColumns.forEach(function (column, index) {
                            result[column] = (items[index] === undefined ? "" : items[index]);
                        });
                        app.$refs.crud.items.post(result);
                    }
                );
            },
        }
    }
</script>

<style>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .modal-container {
        width: 500px;
        max-height: 80%;
        margin: 5px auto;
        padding: 10px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        font-family: Helvetica, Arial, sans-serif;
    }

    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }

    .modal-body {

    }

    .modal-footer {
        margin: 5px;
    }

    .modal-default-button {
        float: right;
        margin: 5px;
    }

    /*
     * The following styles are auto-applied to elements with
     * transition="modal" when their visibility is toggled
     * by Vue.js.
     *
     * You can easily play with the modal transition by editing
     * these styles.
     */

    .modal-enter {
        opacity: 0;
    }

    .modal-leave-active {
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave-active .modal-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }

    #editor {
        width: 100%;
        height: 500px;
        max-width: 100%;
        max-height: 100%;
        border: 1px solid lightgrey;
        background-color: #f6f6f6;
        font-size: 14px;
        font-family: 'Monaco', courier, monospace;
    }

    code {
        color: #f66;
    }
</style>