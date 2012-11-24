App.Views.PlanningView = Backbone.View.extend({

    className: 'planning',

    initialize: function() {
        this.lessons = this.collection;
        this.lessons.on('add change remove reset', this.render, this);
    },

    render: function() {
        var lessons = this.lessons.toArray();
        // Build day views and keep DOM elements for insertion
        var elements = [];
        this.groupByDay(lessons, function(date, lessons) {
            var view = new App.Views.PlanningDayView({
                date: date,
                lessons: lessons
            });
            view.render();
            elements.push(view.el);
        });
        this.$el.append(elements);
        return this;
    },

    groupByDay: function(lessons, iterator) {
        // Lessons are expected in date sorted order
        var lastDate, // The day we are working on
            lessonDate, // The date of of currently evaluated lesson
            lessonBag = []; // Temporary array holding holding lessons between each iterator call

        _.each(lessons, function(lesson) {
            lessonDate = new Date(lesson.get('start'));
            lessonDate.setHours(0,0,0,0); // We strip time components to compare date reliablely
            if (lessonDate > lastDate) {
                // We changed of day: call iterator and reset lesson bag
                iterator(lastDate, lessonBag);
                lessonBag = [];
            }
            lessonBag.push(lesson);
            lastDate = lessonDate;
        });
    }

});