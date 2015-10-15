import React from 'react'
import ReactDOM from 'react-dom'
import { Router, Route, IndexRoute, Link } from 'react-router'
import NewsletterList from 'newsletters/list.jsx'
import NewsletterTypes from 'newsletters/types.jsx'
import NewsletterTemplates from 'newsletters/templates.jsx'
import NewsletterSend from 'newsletters/send.jsx'
import NewsletterStandard from 'newsletters/types/standard.jsx'
import NewsletterWelcome from 'newsletters/types/welcome.jsx'
import createHashHistory from 'history/lib/createHashHistory'

let history = createHashHistory({ queryKey: false })

const App = React.createClass({
  render() {
    return this.props.children
  }
});

let container = document.getElementById('newsletters_container');

if(container) {
  ReactDOM.render((
    <Router history={ history }>
      <Route path="/" component={ App }>
        <IndexRoute component={ NewsletterList } />
        <Route path="new" component={ NewsletterTypes } />
        <Route name="standard" path="new/standard" handler={ NewsletterStandard } />
        <Route name="welcome" path="new/welcome" handler={ NewsletterWelcome } />
        <Route name="template" path="template/:id" handler={ NewsletterTemplates } />
        <Route path="send/:id" component={ NewsletterSend } />
        <Route path="*" component={ NewsletterList } />
      </Route>
    </Router>
  ), container);
}
