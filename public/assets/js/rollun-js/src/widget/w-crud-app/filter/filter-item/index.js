import './index.css'
import widget from '../../../../widget'
import _ from 'lodash'

export default {
    name: 'w-sc-f-filter-item',
    mixins: [widget],
    template: require('./index.html'),
    methods: {
        remove: function () {
            let idx = this.$parent.state.args.indexOf(this.state);
            let args = _.cloneDeep(this.$parent.state.args);
            args.splice(idx, 1);
            this.$parent.args = [];
            this.$nextTick(function () {
                this.$parent.state.args = args;
            });
        }
    }
}
