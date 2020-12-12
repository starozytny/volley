import React, { Component } from 'react';

import { SubMenu }   from "./SubMenu";
import { PageError } from "./PageError";
import { Pagination } from "./Pagination";

export class Page extends Component {
    constructor(props) {
        super(props);

        this.pagination = React.createRef();
    }

    render () {
        const { haveLoadPageError, children,
                havePagination, perPage = "12", taille, data,
        } = this.props;

        return <>
            <SubMenu />
            {haveLoadPageError && <PageError />}
            <div className="main-content">
                {children}
                <Pagination ref={this.pagination} havePagination={havePagination} perPage={perPage} taille={taille} items={data}
                            onUpdate={(items) => this.props.onUpdate(items)}/>
            </div>

        </>
    }
}