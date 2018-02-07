import './index.css'
import widget from '../../widget'

export default {
    name: 'w-checkbox',
    mixins: [widget],
    template: require('./index.html'),
    computed: {
        value: {
            get: function () {
                return this.state[this.options.bind] === "1" || this.state[this.options.bind] === 1;
            },
            set: function (value) {
                this.state[this.options.bind] = value ? 1 : 0;
            }
        }
    }
}
