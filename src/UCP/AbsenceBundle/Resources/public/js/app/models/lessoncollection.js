App.Models.LessonCollection = Backbone.Collection.extend({

    model: App.Models.Lesson,

    url: Routing.generate('get_lessons')

});
