import $ from 'jquery'
import './index.css'
import widget from '../../widget'
import wFilterGroup from './filter/filter-group'
import wGroupBy from './group-by'
import wAggregates from './aggregates'

export default  {
    name: 'w-crud-app',
    mixins: [widget],
    props: ['title', 'url'],
    template: require('./index.html'),
    components: {
        'w-filter-group': wFilterGroup,
        'w-group-by': wGroupBy,
        'w-aggregates': wAggregates
    },
    data: function () {
        let self = this;
        return {
            edit_mode: false,
            items: this.$ds(this.url, {
                on_load: function (items) {
                    $(self.$refs.loadingIcon).hide();
                    $(self.$refs.dataTable).show();
                    _.debounce(function () {
                        $(self.$refs.btn_refresh).removeClass('fa-spin');
                    }, 300)();
                }
            }),
            pages: [2, 10, 20, 50, 100],
            search_text: null,
            filter_bar: false,
            group_bar: false,
            aggregates_bar: false,
            request: {
                offset: 0,
                limit: 10,
                sort: {column: null, direction: null},
                filter: {op: '$and', args: []},
                group: [],
                aggregates: {},
            }
        }
    },
    methods: {
        reset: function () {
            this.search_text = null;
            this.request = {
                offset: 0,
                limit: 10,
                sort: {column: null, direction: null},
                filter: {op: '$and', args: []},
                group: [],
                aggregates: {},
            };
            this.items.schema({});
            this.refresh();
        },
        get_request: function () {
            return this.request;
        },
        refresh: function () {
            $(this.$refs.btn_refresh).addClass('fa-spin');
            this.items.fetch(this.get_request());
        },
        apply_filter: function () {
            this.refresh();
        },
        clear_filter: function () {
            this.request.filter = {op: '$and', args: []};
        },
        edit_form: function () {
            let edit_form = {
                widgets: []
            };
            _.forEach(this.items.schema(), function (column, column_name) {
                edit_form.widgets.push({
                    type: 'w-string',
                    label: column_name,
                    bind: column_name,
                    disabled: column.pk
                });
            });
            return edit_form;
        },
        new_item: function () {
            let self = this;
            self.edit_mode = true;
            this.$refs.edit_form.show({
                state: {},
                form: this.edit_form()
            }).then(function (modal) {
                    self.items.post(modal.state).then(function () {
                        self.$refs.edit_form.hide();
                        self.refresh();
                        self.edit_mode = false;
                    });
                },
                function () {
                    self.$refs.edit_form.hide();
                    self.edit_mode = false;
                }
            );
        },
        edit_item: function (item) {
            let self = this;
            this.edit_mode = true;
            this.$refs.edit_form.show({
                state: _.cloneDeep(item),
                form: this.edit_form()
            }).then(function (modal) {
                    self.items.put(modal.state).then(function () {
                        self.$refs.edit_form.hide();
                        self.refresh();
                        self.edit_mode = false;
                    });
                },
                function () {
                    self.$refs.edit_form.hide();
                    self.edit_mode = false;
                }
            );
        },
        remove_item: function (item) {
            let self = this;
            bootbox.confirm("Удалить ? Вы уверенны ?", function (result) {
                if (result) {
                    self.items.remove(item).then(function () {
                        self.refresh();
                    });

                }
            });
        },
        total: function () {
            return this.items.count()
        },
        count: function () {
            return ((this.request.offset + this.request.limit) > this.total()
            || (this.request.offset + this.request.limit) <= 0)
                ? this.total()
                : this.request.offset + this.request.limit;
        },
        set_limit: function (limit) {
            this.request.offset = 0;
            this.request.limit = limit;
            this.refresh();
        },
        next_page: function () {
            if (this.request.offset + this.request.limit < this.total()) {
                this.request.offset = this.request.offset + this.request.limit;
            }
            this.refresh();
        },
        prev_page: function () {
            this.request.offset = this.request.offset - this.request.limit;
            if (this.request.offset < 0) {
                this.request.offset = 0;
            }
            this.refresh();
        },
        order_by(column){
            if (column.sortable) {
                this.request.sort.direction = this.request.sort.direction === 'asc' ? 'desc' : 'asc';
                this.request.sort.column = column.bind;
                this.refresh();
            }
        },
        search: function () {
            let args = [];
            let self = this;
            this.request.filter.op = '$and';
            this.request.filter.args = [];
            this.$nextTick(function () {
                _.forEach(this.items.schema(), function (column, column_name) {
                    if (column.search) {
                        args.push({
                            column: column.full_name || column_name,
                            expr: '$like',
                            value: self.search_text,
                            not: false
                        })
                    }
                });
                this.request.filter.op = '$or';
                this.request.filter.args = args;
                this.request.offset = 0;
                this.refresh();
            })
        },
    }
}
