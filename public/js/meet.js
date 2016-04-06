Vue.component('events-event', {
    template: '#event-template',

    props: ['meet'],

    data: function () {
        return {
            events: {}
        };
    },

    created: function () {
        this.fetchEventList(this.meet);
    },

    methods: {
        fetchEvent: function (meet, event, round, heat) {
            var resource = this.$resource('/api/meet-event/{meet}/{event}{/round}{/heat}');

            var params = {meet: meet};
            if (event !== null) {
                params.event = event;
            }
            if (round !== null) {
                params.round = round;
            }
            if (heat !== null) {
                params.heat = heat;
            }

            resource.get(params).then(function (response) {
                    this.updateData(response.data);
                }.bind(this),
                function () {
                    this.fetchEvent(meet, event)
                }.bind(this)
            );
        },

        fetchEventList: function (meet) {
            var resource = this.$resource('/api/meet-event/{meet}');

            resource.get({meet: meet}).then(function (response) {
                var events = response.data.events;

                for (var index = 0, len = events.length; index < len; ++index) {
                    this.fetchEvent(meet, events[index]);
                }

            }.bind(this));
        },

        updateData: function (data) {
            this.events = Object.assign({}, this.events, data);
        }
    }
});

var vm = new Vue({
    el: '#vue',

    methods: {
        update: function (event, round, heat) {
            // TODO: How to access without $children[0]
            this.$children[0].fetchEvent(meetId, event, round, heat);
        },

        updateData: function (data) {
            // TODO: How to access without $children[0]
            this.$children[0].updateData(data);
        }
    }
});
