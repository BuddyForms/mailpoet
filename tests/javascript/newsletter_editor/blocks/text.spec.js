describe('Text', function () {
    describe('model', function () {
        var model;
        beforeEach(function () {
            global.stubChannel();
            global.stubConfig();
            model = new (EditorApplication.module('blocks.text').TextBlockModel)();
        });

        it('has a text type', function () {
            expect(model.get('type')).to.equal('text');
        });

        it('has text', function () {
            expect(model.get('text')).to.be.a('string');
        });

        it("uses defaults from config when they are set", function () {
            global.stubConfig({
                blockDefaults: {
                    text: {
                        text: 'some custom config text',
                    },
                },
            });
            var model = new (EditorApplication.module('blocks.text').TextBlockModel)();

            expect(model.get('text')).to.equal('some custom config text');
        });
    });

    describe('block view', function () {
        global.stubConfig();
        var model = new (EditorApplication.module('blocks.text').TextBlockModel)(),
            view = new (EditorApplication.module('blocks.text').TextBlockView)({model: model});

        it('renders', function () {
            expect(view.render).to.not.throw();
            expect(view.$('.mailpoet_content')).to.have.length(1);
        });

        describe('once rendered', function () {
            var model = new (EditorApplication.module('blocks.text').TextBlockModel)(),
                view;

            beforeEach(function () {
                global.stubConfig();
                view = new (EditorApplication.module('blocks.text').TextBlockView)({model: model});
                view.render();
            });

            it('has a deletion tool', function () {
                expect(view.$('.mailpoet_delete_block')).to.have.length(1);
            });

            it('has a move tool', function () {
                expect(view.$('.mailpoet_move_block')).to.have.length(1);
            });

            it('does not have a settings tool', function () {
                expect(view.$('.mailpoet_edit_block')).to.have.length(0);
            });
        });
    });
});
