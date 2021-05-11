import React, { Component } from 'react';

import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Button, ButtonIcon } from "@dashboardComponents/Tools/Button";

import { Search }     from "@dashboardComponents/Layout/Search";
import { Alert }      from "@dashboardComponents/Tools/Alert";

export class ArticlesList extends Component {

    render () {
        const { data, onChangeContext, onSearch, onDeleteAll } = this.props;

        return <>
            <div>
                <div className="toolbar">
                    <div className="item create">
                        <Button onClick={() => onChangeContext("create")}>Ajouter un article</Button>
                    </div>
                    <div className="item filter-search">
                        <Search onSearch={onSearch} />
                    </div>
                </div>

                <div className="items-table">
                    <div className="items items-default items-user">
                        {data && data.length !== 0 ? data.map(elem => {
                            return null
                        }) : <Alert>Aucun résultat</Alert>}
                    </div>
                </div>

                <div className="page-actions">
                    <div className="selectors-actions">
                        <div className="item" onClick={onDeleteAll}>
                            <ButtonIcon icon="trash" text="Supprimer la sélection" />
                        </div>
                    </div>
                </div>

            </div>
        </>
    }
}