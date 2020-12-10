import React, { Component } from 'react';

import { Toolbar }   from "./Toolbar";
import { PageError } from "./PageError";

export class Page extends Component {
    render () {
        const { haveLoadPageError, children } = this.props;

        return <>
            <Toolbar />
            {haveLoadPageError && <PageError />}
            <div className="main-content">{children}</div>
        </>
    }
}