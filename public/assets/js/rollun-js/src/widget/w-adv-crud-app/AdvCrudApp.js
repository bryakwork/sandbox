import '../w-crud-app/index.css'
import widget from '../../widget'
import wCrudApp from '../w-crud-app';

export default {
    mixins: [wCrudApp, widget],
    name: "w-crud-app-plus",
    template: require('../w-crud-app/index.html'),
    methods: {
        edit_form: function () {
            var edit_form = {
                widgets: []
            };
            _.forEach(this.items.schema(), function (column, column_name) {
                edit_form.widgets.push({
                    type: (app.$refs.crud.options.widget_type[column_name] === undefined ? "w-string" : app.$refs.crud.options.widget_type[column_name]),
                    label: column_name,
                    bind: column_name,
                    disabled: column.pk
                });
            });
            return edit_form;
        },
    }
}