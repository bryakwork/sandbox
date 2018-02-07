/**
 *
 * @param store
 * @param options
 * @constructor
 */
function DojoDataSource(store, options) {
    options = options || {};
    this._pk_name = options.pk_name || 'id';
    this._items = null;
    this._count = 0;
    this._request = {offset: 0, limit: 10};
    this._store = store;
    this._loaded = false;
    this._schema = options.schema || {};
    this._post_process = options.post_process;
    this._on_load = options.on_load;
    //this._filter = _.clone(store);
}

/**
 *
 * @param idx
 * @returns {*}
 */
DojoDataSource.prototype.items = function (idx) {
    if (_.isNumber(idx))
        return this._items[idx];
    return this._items;
};

/**
 *
 * @returns {number|*}
 */
DojoDataSource.prototype.count = function () {
    return this._count;
};

/**
 *
 * @param id
 * @returns {{}}
 */
DojoDataSource.prototype.item = function (id) {
    return _.find(this._items, [this._pk_name, id]) || {};
};
/**
 *
 * @param request
 * @param post_process
 * @returns {*|null}
 */
DojoDataSource.prototype.query = function (request, post_process) {
    if (!this._loaded) {
        this.fetch(request, post_process);
    }
    return this._items;
};

/**
 *
 * @returns {{}|*}
 */
DojoDataSource.prototype.schema = function (schema) {
    if (schema) this._schema = {};
    return this._schema;
};

DojoDataSource.prototype.post = function (item) {
    return this._store.add(item);
};

DojoDataSource.prototype.put = function (item) {
    return this._store.put(item);
};

DojoDataSource.prototype.save = function (item) {
    if (item.id) {
        return this.put(item);
    }
    return this.post(item);
};

DojoDataSource.prototype.remove = function (item) {
    return this._store.remove(item[this._pk_name]);
};

DojoDataSource.prototype.fetch = function (request, post_process) {
    let self = this;
    let filter = null;
    let Q = Query;
    let rql = [];

    request = request || this._request;
    post_process = post_process || this._post_process;

    if (request.sort && request.sort.column && request.sort.direction) {
        filter = this._store.sort(request.sort.column, request.sort.direction === 'desc');
    } else {
        filter = this._store.filter();
    }

    if (request.group && request.group.length > 0) {
        request.select = _.cloneDeep(request.group);
    } else if (!_.isEmpty(this.schema())) {
        request.select = _.keys(this.schema());
    }

    if (request.aggregates && _.keys(request.aggregates).length > 0) {
        let aggregates = [];
        _.forEach(request.aggregates, function (as, column) {
            _.forEach(as, function (aggregate) {
                aggregates.push(aggregate + '(' + column + ')');
            });
        });
        request.select = _.concat(request.select, aggregates);
    }

    if (request.select) rql.push("select(" + request.select.join(',') + ")");
    if (request.group.length > 0) rql.push("groupby(" + request.group.join(',') + ")");

    if (request.filter) {
        let query = Q();
        const make_query = function (group) {
            const op = group.op;
            let args = [];
            group.args.forEach(function (arg) {
                if (arg.op === '$and' || arg.op == '$or') {
                    //args.push(make_query(arg));
                } else if (arg.expr !== '$no_column' && !_.isEmpty(arg.value)) {
                    if (arg.expr === '$like') {
                        args.push(Q().contains(arg.column, arg.value).args[0]);
                    }
                    if (arg.expr === '$not_like') {
                        args.push(Q().not(Q().contains(arg.column, arg.value)).args[0]);
                    }
                    if (arg.expr === '$eq') {
                        args.push(Q().eq(arg.column, arg.value).args[0]);
                    }
                    if (arg.expr === '$ne') {
                        args.push(Q().ne(arg.column, arg.value).args[0]);
                    }
                    if (arg.expr === '$lt') {
                        args.push(Q().lt(arg.column, arg.value).args[0]);
                    }
                    if (arg.expr === '$gt') {
                        args.push(Q().gt(arg.column, arg.value).args[0]);
                    }
                    if (arg.expr === '$le') {
                        args.push(Q().le(arg.column, arg.value).args[0]);
                    }
                    if (arg.expr === '$ge') {
                        args.push(Q().ge(arg.column, arg.value).args[0]);
                    }
                }
            });
            let q = Q();
            if (args.length > 1) {
                if (op === '$or') {
                    q = Q().or.apply(query, args);
                }
                if (op === '$and') {
                    q = Q().and.apply(query, args);
                }
            } else {
                q = Q.apply(query, args);
            }
            return q;
        };
        query = make_query(request.filter);
        if (query.args.length > 0) rql.push(query.toString());
    }

    if (rql.length > 0) filter = filter.filter(rql.join('&'));

    let result = filter.fetchRange({start: request.offset, end: request.offset + request.limit});

    result.totalLength.then(function (count) {
        self._loaded = true;
        self._count = count;
    });

    result.then(function (data) {
        self._loaded = true;
        if (_.isFunction(post_process)) data = post_process(data);
        if (data.length > 0) {
            self._schema = {};
            let _schema = {};
            let row = data[0];
            let keys = _.keys(row);
            _.forEach(keys, function (k) {
                _schema[k] = {
                    pk: self._pk_name === k,
                    title: k,
                    sortable: true,
                    bind: k,
                    filter: true,
                    search: true,
                    group: true,
                    type: 'string',
                    aggregates: ['count', 'sum', 'min', 'max', 'avg']
                };
            });
            self._schema = _schema;
        }
        self._items = data;
        if (_.isFunction(self._on_load)) self._on_load(self._items);
    });

    return result;
};

/*



 DojoDataSource.prototype.load = function (id) {
 return $.ajax({
 url: this._url + '/' + id,
 type: 'GET'
 });
 };
 */

export default DojoDataSource;
