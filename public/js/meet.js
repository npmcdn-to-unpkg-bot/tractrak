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

                for (index = 0, len = events.length; index < len; ++index) {
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
        update: function (meet, event, round, heat) {
            // TODO: How to access without $children[0]
            this.$children[0].fetchEvent(meet, event, round, heat);
        },

        updateData: function (data) {
            // TODO: How to access without $children[0]
            this.$children[0].updateData(data);
        }
    },

    filters: {
        myOrderBy: myOrderBy
    }
});

var myOrderBy = function (arr, sortKey, reverse) {
    var order = (reverse && reverse < 0) ? -1 : 1;
    // sort on a copy to avoid mutating original array
    // http://www.overset.com/2008/09/01/javascript-natural-sort-algorithm-with-unicode-support/
    /*
     * Natural Sort algorithm for Javascript - Version 0.7 - Released under MIT license
     * Author: Jim Palmer (based on chunking idea from Dave Koelle)
     */

    return arr.slice().sort(function (a, b) {
        var re = /(^-?[0-9]+(\.?[0-9]*)[df]?e?[0-9]?$|^0x[0-9a-f]+$|[0-9]+)/gi,
            sre = /(^[ ]*|[ ]*$)/g,
            dre = /(^([\w ]+,?[\w ]+)?[\w ]+,?[\w ]+\d+:\d+(:\d+)?[\w ]?|^\d{1,4}[\/\-]\d{1,4}[\/\-]\d{1,4}|^\w+, \w+ \d+, \d{4})/,
            hre = /^0x[0-9a-f]+$/i,
            ore = /^0/,
            i = function(s) { return naturalSort.insensitive && (''+s).toLowerCase() || ''+s },
        // convert all to strings strip whitespace
            x = i(a).replace(sre, '') || '',
            y = i(b).replace(sre, '') || '',
        // chunk/tokenize
            xN = x.replace(re, '\0$1\0').replace(/\0$/,'').replace(/^\0/,'').split('\0'),
            yN = y.replace(re, '\0$1\0').replace(/\0$/,'').replace(/^\0/,'').split('\0'),
        // numeric, hex or date detection
            xD = parseInt(x.match(hre)) || (xN.length != 1 && x.match(dre) && Date.parse(x)),
            yD = parseInt(y.match(hre)) || xD && y.match(dre) && Date.parse(y) || null,
            oFxNcL, oFyNcL;
        // first try and sort Hex codes or Dates
        if (yD)
            if ( xD < yD ) return -1;
            else if ( xD > yD ) return 1;
        // natural sorting through split numeric strings and default strings
        for(var cLoc=0, numS=Math.max(xN.length, yN.length); cLoc < numS; cLoc++) {
            // find floats not starting with '0', string or 0 if not defined (Clint Priest)
            oFxNcL = !(xN[cLoc] || '').match(ore) && parseFloat(xN[cLoc]) || xN[cLoc] || 0;
            oFyNcL = !(yN[cLoc] || '').match(ore) && parseFloat(yN[cLoc]) || yN[cLoc] || 0;
            // handle numeric vs string comparison - number < string - (Kyle Adams)
            if (isNaN(oFxNcL) !== isNaN(oFyNcL)) { return (isNaN(oFxNcL)) ? 1 : -1; }
            // rely on string comparison if different types - i.e. '02' < 2 != '02' < '2'
            else if (typeof oFxNcL !== typeof oFyNcL) {
                oFxNcL += '';
                oFyNcL += '';
            }
            if (oFxNcL < oFyNcL) return -1;
            if (oFxNcL > oFyNcL) return 1;
        }
        return 0;
    })
};
