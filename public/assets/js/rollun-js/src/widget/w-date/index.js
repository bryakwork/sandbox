import './index.css'
import widget from '../../widget'
import moment from 'moment'

export default {
    name: 'w-date',
    mixins: [widget],
    template: require('./index.html'),
    computed: {
        value: {
            get: function () {
                let value = moment(this.state[this.options.bind]).format("YYYY-MM-DD");
                return value !== 'Invalid date' ? value : '--';

            },
            set: function (value) {
                this.state[this.options.bind] = value;
            }
        }
    }
}
