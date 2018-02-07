import Vue from 'vue'
import './index.css'
import widget from '../../../../widget'
import wFilterItem from '../filter-item'

export default  {
    name: 'w-sc-f-filter-group',
    mixins: [widget],
    components: {
        'w-sc-f-filter-item': wFilterItem
    },
    template: require('./index.html'),
    methods: {
        remove: function () {
            if (this.state.is_child) {
                let idx = this.$parent.state.args.indexOf(this.state);
                Vue.delete(this.$parent.state.args, idx);
            }
        },
        add_group: function (op) {
            this.state.args.push({op: op, args: [], is_child: true})
        },
        add_filter: function (column, expr, value) {
            this.state.args.push({column: column, expr: expr, value: value, not: false})
        }
    }
};
