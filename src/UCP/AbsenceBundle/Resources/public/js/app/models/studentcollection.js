App.Models.StudentCollection = Backbone.Collection.extend({

    model: App.Models.Student,

    url: Routing.generate('get_students')

});
