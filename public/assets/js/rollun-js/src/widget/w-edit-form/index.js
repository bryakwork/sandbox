import './index.css'
import widget from '../../widget'

export default {
    name: 'w-edit-form',
    mixins: [widget],
    template: require('./index.html'),
    data(){
        return {
            visible: false,
            form: {},
            state: {}
        }
    },
    methods: {
        hide: function () {
            this.visible = false;
        },
        show: function (data) {
            let self = this;
            if (data) {
                this.form = {};
                this.state = {};
                this.$nextTick(function () {
                    self.form = data.form || self.form;
                    self.state = data.state || self.state;
                });
            }
            this.visible = true;
            return this.promise();
        }
    }
};

