import './index.css'
import widget from '../../../widget'
import _ from 'lodash'

export default  {
    name: 'w-sc-group-by',
    mixins: [widget],
    template: require('./index.html'),
    methods: {
        in_group_by: function (column) {
            return _.includes(this.state.group, column);
        },
        toggle_column: function (column) {
            if (this.in_group_by(column)) {
                this.state.group = _.without(this.state.group, column);
            } else {
                this.state.group.push(column);
            }
        }
    }
}
