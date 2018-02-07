import './index.css'
import widget from '../../../widget'
import _ from 'lodash'

export default  {
    name: 'w-sc-aggregates',
    mixins: [widget],
    template: require('./index.html'),
    methods: {
        in_aggregates: function (column, aggregate) {
            if (_.has(this.state.aggregates, column)) {
                return _.includes(this.state.aggregates[column], aggregate);
            }
            return false;
        },
        toggle_aggregate: function (column, aggregate) {
            if (this.in_aggregates(column, aggregate)) {
                this.state.aggregates[column] = _.without(this.state.aggregates[column], aggregate);
                if (this.state.aggregates[column].length == 0) {
                    this.$delete(this.state.aggregates, column);
                }
            } else {
                if (!_.has(this.state.aggregates, column)) {
                    this.$set(this.state.aggregates, column, []);
                }
                this.state.aggregates[column].push(aggregate);
            }
        }
    }
}
