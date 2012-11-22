App.Views.PlanningDayView = Backbone.View.extend({

    // Expects 'date' and 'lessons' to be given to constructor

    initialize: function() {
        this.template = Handlebars.templates['planning-day'];
    },

    render: function() {
        // Render date heading
        this.$el.html( this.template({ date: this.options.date }) );

        // Append each planning item
        var elements = _.map(this.options.lessons, function(lesson) {
            var view = new App.Views.PlanningLessonView({ lesson: lesson });
            view.render();
            return view.el;
        });
        this.$el.append(elements);

        return this;
    }

});