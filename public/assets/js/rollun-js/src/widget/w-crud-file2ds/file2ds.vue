<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <ul v-show="uploadedFiles.length > 0">
                        <!-- loop through the completed files -->
                        <li v-for="file in uploadedFiles">
                            Name: <em>{{ file.name }}</em> Size: <em>{{prettyBytes(file.size)}}</em></li>
                    </ul>
                    <!-- message for all uploads completing -->
                    <p v-if="allFilesUploaded"><strong>All Files Uploaded</strong></p>
                    <!-- only show when ready, fileProgress is a percent -->
                    <div class="progress-bar" v-bind:style="fileProgressStyle" v-show="fileProgress > 0" ></div>
                    <label for="file2ds-input" id="inputlabel">
                        {{fileinputtext}}
                    </label>
                    <input type="file" id="file2ds-input"
                           v-bind:name="formName"
                           v-bind:accept="accept"
                           v-on:click="fileInputClick"
                           v-on:change="fileInputChange">
                    <label for="file2ds-delim" id="delimlabel">
                        {{delimInputText}}
                    </label>
                    <select id="file2ds-delim" v-model="delimeter">
                        <option v-for="option in options" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                    <div class="progress-bar" v-show="fileProgress > 0"
                         v-bind:style="fileProgressStyle">
                    </div>
                    <div>
                        <button type="button" class="modal-default-button btn btn-primary btn-md"
                                v-on:click="fileUpload">
                            Загрузить
                        </button>
                        <button class="modal-default-button btn btn-default btn-md" v-on:click="$emit('close')">
                            Отменить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    //TODO: get upload progress bar to work
    import mitt from 'mitt';

    export default {
        name: "w-crud-file2ds",
        inject: [
            'formName',
            'action',
            'accept',
            'headers'
        ],
        data: function () {
            return {
                uploadedFiles: [], // my list for the v-for
                fileProgress: 0, // global progress
                allFilesUploaded: false, // is everything done?
                fileinputtext: 'Выберите файл',
                options: [
                    {text: 'Запятая', value: ','},
                    {text: 'Точка с запятой', value: ';'},
                    {text: 'Табуляция', value: '\t'},
                    {text: 'Проюел', value: ' '}
                ],
                delimeter: ',',
                delimInputText: 'Выберите разделитель'
            };
        },
        computed: {
            fileProgressStyle: function () {
                return "width: " + this.fileProgress + "%";
            },
        },
        methods: {
            fileInputClick: function () {
                // click actually triggers after the file dialog opens
            },
            fileInputChange: function () {
                // get the group of files assigned to this field
                this.myFiles = document.getElementById('file2ds-input').files;
                this.fileProgress = 0;
                this.allFilesUploaded = false;
            },
            _onProgress: function (event) {
                // this is an internal call in XHR to update the progress
                event.percent = (event.loaded / event.total) * 100;
                this.fileProgress = event.percent;
                this.$emit('onFileProgress', event);
            },
            _handleUpload: function (file, delimeter) {
                this.$emit('beforeFileUpload', file);
                var form = new FormData();
                var xhr = new XMLHttpRequest();
                try {
                    form.append('Content-Type', file.type || 'application/octet-stream');
                    // our request will have the file in the ['file'] key
                    form.append('file', file);
                    form.append('delimeter', delimeter);
                } catch (err) {
                    this.$emit('onFileError', file, err);
                    return;
                }

                return new Promise(function (resolve, reject) {

                    xhr.upload.addEventListener('onprogress', this._onProgress, false);

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState < 4) {
                            return;
                        }
                        if (xhr.status < 400) {
                            var res = JSON.parse(xhr.responseText);
                            this.$emit('onFileUpload', file, res);
                            resolve(file);
                        } else {
                            var err = JSON.parse(xhr.responseText);
                            err.status = xhr.status;
                            err.statusText = xhr.statusText;
                            this.$emit('onFileError', file, err);
                            reject(err);
                        }
                    }.bind(this);

                    xhr.onerror = function () {
                        var err = JSON.parse(xhr.responseText);
                        err.status = xhr.status;
                        err.statusText = xhr.statusText;
                        this.$emit('onFileError', file, err);
                        reject(err);
                    }.bind(this);

                    xhr.open('POST', JSON.parse(this.action), true);
                    xhr.setRequestHeader('Accept', 'application/json');
                    if (this.headers) {
                        for (var header in this.headers) {
                            xhr.setRequestHeader(header, this.headers[header]);
                        }
                    }
                    xhr.send(form);
                    this.$emit('afterFileUpload', file);
                }.bind(this));
            },
            fileUpload: function () {
                if (this.myFiles.length > 0) {
                    // a hack to push all the Promises into a new array
                    var arrayOfPromises = Array.prototype.slice.call(this.myFiles, 0).map(function (file) {
                        return this._handleUpload(file, this.delimeter);
                    }.bind(this));
                    // wait for everything to finish
                    Promise.all(arrayOfPromises).then(function (allFiles) {
                        this.allFilesUploaded = true;
                        this.$emit('onAllFilesUploaded', allFiles);
                    }.bind(this)).catch(function (err) {
                        this.$emit('onFileError', this.myFiles, err);
                    }.bind(this));
                    /*this.$emit('close')*/
                } else {
                    // someone tried to upload without adding files
                    var err = new Error("No files to upload for this field");
                    this.uploadedFiles.push(file);
                    this.$emit('onFileError', this.myFiles, err);
                    this.$emit('close');
                }
            },
            prettyBytes: function (num) {
                // jacked from: https://github.com/sindresorhus/pretty-bytes
                if (typeof num !== 'number' || isNaN(num)) {
                    throw new TypeError('Expected a number');
                }
                var exponent;
                var unit;
                var neg = num < 0;
                var units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

                if (neg) {
                    num = -num;
                }
                if (num < 1) {
                    return (neg ? '-' : '') + num + ' B';
                }
                exponent = Math.min(Math.floor(Math.log(num) / Math.log(1000)), units.length - 1);
                num = (num / Math.pow(1000, exponent)).toFixed(2) * 1;
                unit = units[exponent];

                return (neg ? '-' : '') + num + ' ' + unit;
            }
        }
    }

</script>

<style scoped>
    .progress-bar {
        opacity: 1;
        height: 10px;
        margin: 0.4em 0;
        width: 0;
        background: green;
    }

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
        padding: 20px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        font-family: Helvetica, Arial, sans-serif;
    }
</style>