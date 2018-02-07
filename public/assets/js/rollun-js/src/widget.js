import _ from 'lodash'

export default {
    props: {
        state: {
            type: Object,
            default: function () {
                return {}
            }
        },
        options: {
            type: Object,
            default: function () {
                return {}
            }
        }
    },
    mounted: function () {
        if (this.options.subscriptions) {
            let self = this;
            _.forEach(this.options.subscriptions, function (callback, subscription_name) {
                self.sub(subscription_name, function () {
                    return callback.apply(self, arguments);
                });
            });
        }
        if (this.options.uid) {
            this.$boot.set(this.options.uid, this);
            this.pub("mounted:" + this.options.uid, this);
        }
    },
    computed: {
        disabled: function () {
            if (_.isBoolean(this.options.disabled))return this.options.disabled;
            if (_.isFunction(this.options.disabled)) return this.options.disabled.apply(this);
            return false
        },
        visibility: function () {
            if (_.isBoolean(this.options.visibility)) return this.options.visibility;
            if (_.isFunction(this.options.visibility)) return this.options.visibility.apply(this);
            return true
        }
    },
    methods: {
        boot: function (uid) {
            return this.$boot.get(uid);
        },
        sub: function (action_name, callback) {
            this.$bus.on(action_name, callback);
        },
        pub: function (action_name, msg) {
            this.$bus.emit(action_name, msg);
        },
        promise: function () {
            this._deferred = new $.Deferred();
            return this._deferred.promise();
        }
        ,
        submit: function () {
            if (this.options) {
                if (_.isString(this.options.submit)) this.pub(this.options.submit, this.s);
                if (_.isFunction(this.options.submit)) this.options.submit.apply(this);
            }
            if (this._deferred) this._deferred.resolve(this);
        }
        ,
        cancel: function () {
            if (this.options) {
                if (_.isString(this.options.cancel)) this.$bus.emit(this.options.cancel, this.s);
                if (_.isFunction(this.options.cancel)) this.options.cancel.apply(this);
            }
            if (this._deferred) this._deferred.reject();
        }
        ,
        onclick: function () {
            if (_.isString(this.options.onclick)) return this.pub(this.options.onclick, this);
            if (_.isFunction(this.options.onclick)) return this.options.onclick.apply(this);
        }
        ,
        onkeyup: function (e) {
            if (_.isString(this.options.onkeyup)) return this.pub(this.options.onkeyup, this);
            if (_.isFunction(this.options.onkeyup)) return this.options.onkeyup.apply(this);
        }
        ,
        onchange: function (e) {
            if (_.isString(this.options.onchange)) return this.pub(this.options.onchange, this);
            if (_.isFunction(this.options.onchange)) return this.options.onchange.apply(this);
        }
        ,
    }
}
