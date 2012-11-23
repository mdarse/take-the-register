App.Views.PlanningLessonView = Backbone.View.extend({
    
    tagName: 'article',
    className: 'lesson',
    
    initialize: function() {
        this.template = Handlebars.templates['planning-lesson'];
    },

    render: function() {
        this.$el.html(this.template( this.options.lesson.toJSON() ));
        return this;
    }
});