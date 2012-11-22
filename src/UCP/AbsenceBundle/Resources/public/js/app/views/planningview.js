App.Views.PlanningView = Backbone.View.extend({

    initialize: function() {
        this.lessons = this.collection;
        this.lessons.on('add change remove reset', this.render, this);
    },

    render: function() {
        var lessons = this.lessons.toArray();
        for (var i = 0, l = lessons.length; i < l; i++) {
            var view = new App.Views.PlanningDayView({
                date: lessons[i].get('start'),
                lessons: [lessons[i]]
            });
            view.render();
            this.$el.append(view.el);
        };
        return this;
    }

});