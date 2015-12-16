/**
 * boneyard.js - Base application logic.
 *
 * Copyright 2015-2016 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @author Ralf Lang <lang@b1-systems.de>
 * This is mostly based on work by Michael Rubinsky
 */

BoneyardCore = {
    /* Onload function. */

    view: '',
    effectDur: 0.4,
    viewLoading: [],
    onDomLoad: function()
    {
        document.observe('click', BoneyardCore.clickHandler.bindAsEventListener(BoneyardCore));
        // Initialize the starting page.
        var tmp = location.hash;
        if (!tmp.empty() && tmp.startsWith('#')) {
            tmp = (tmp.length == 1) ? '' : tmp.substring(1);
        }
        this.go(Boneyard.conf.login_view);
    },
    /**
     *  Go to a different location/view inside the app
     */
    go: function(fullloc, data)
    {
        if (this.viewLoading.size()) {
            this.viewLoading.push([ fullloc, data ]);
            return;
        }
        var locParts = fullloc.split(':'),
            loc = locParts.shift(),
            locCap;

        if (this.openLocation == fullloc) {
            return;
        }
        this.viewLoading.push([ fullloc, data ]);
        switch (loc) {
        case 'example1':
        case 'example2':
            this.closeView(loc);
            locCap = loc.capitalize();
            this.updateView(loc);
            var id = locParts.shift();
        default:
            this.view = loc;
            $('boneyardView' + locCap).appear({
                duration: this.effectDur,
                queue: 'end',
                afterFinish: function() {
                    this.loadNextView();
                }.bind(this)});
            break;
            this.loadNextView();
            break;
        }
    },
    /**
     * Removes the last loaded view from the stack and loads the last added
     * view, if the stack is still not empty.
     *
     * We want to load views from a LIFO queue, because the queue is only
     * building up if the user switches to another view while the current view
     * still loads. In that case we can go directly to the most recently
     * clicked view and drop the remaining queue.
     */
    loadNextView: function()
    {
        var current = this.viewLoading.shift(),
            next;
        if (this.viewLoading.size()) {
            next = this.viewLoading.pop();
            this.viewLoading = [];
            if (current[0] != next[0] || current[1] || next[1]) {
                this.go(next[0], next[1]);
            }
        }
    },

   /**
     * The click handler.
     */
    clickHandler: function(e, dblclick)
    {
        var slice, sid, elt, id;

        if (e.isRightClick() || typeof e.element != 'function') {
            return;
        }

        elt = e.element();
        while (Object.isElement(elt)) {
            id = elt.readAttribute('id');
            switch (id) {
            // Main navigation links
            case 'boneyardNavExample1':
                this.go('example1');
                e.stop();
                return;
            case 'boneyardNavExample2':
                this.go('example2');
                e.stop();
                return;
            }
            elt = elt.up();
        }

        // Workaround Firebug bug.
        Prototype.emptyFunction();
    },

    /**
     * Closes the currently active view.
     */
    closeView: function(loc)
    {
        if (this.view && this.view != loc) {
            $('boneyardView' + this.view.capitalize()).fade({
                duration: this.effectDur,
                queue: 'end'
            });
            this.view = null;
        }
    },

    /**
     * Perform any tasks needed to update a view.
     *
     * @param view  The view to update.
     */
    updateView: function(view)
    {
    }
}

document.observe('dom:loaded', BoneyardCore.onDomLoad.bind(BoneyardCore));
