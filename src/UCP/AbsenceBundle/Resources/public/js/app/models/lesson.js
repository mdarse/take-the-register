App.Models.Lesson = Backbone.Model.extend({

    url: function() {
        return Routing.generate('get_lesson', { lesson: this.get('id') });
    }

});
