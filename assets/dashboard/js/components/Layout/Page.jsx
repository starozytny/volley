import React, { Component } from 'react';

import { Toolbar }   from "./Toolbar";
import { PageError } from "./PageError";
import { Pagination } from "./Pagination";

export class Page extends Component {
    render () {
        const { haveLoadPageError, children,
                havePagination, perPage = "12", taille, data,
        } = this.props;

        return <>
            <Toolbar />
            {haveLoadPageError && <PageError />}
            <div className="main-content">
                {children}
                {havePagination && <Pagination perPage={perPage} taille={taille} items={data} onUpdate={(items) => this.props.onUpdate(items)}/>}
            </div>

        </>
    }
}