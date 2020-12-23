import React, { Component } from 'react';

import {Button, ButtonIcon} from "@dashboardComponents/Tools/Button";

import { Filter }    from "@dashboardComponents/Layout/Filter";
import { Search }    from "@dashboardComponents/Layout/Search";

import { UserItem }   from "./UserItem";

export class UserList extends Component {
    constructor(props) {
        super(props);

        this.filter = React.createRef();

        this.handleFilter = this.handleFilter.bind(this);
    }

    handleFilter = (e) => {
        this.filter.current.handleChange(e, true);
    }

    render () {
        const { data, onChangeContext, onGetFilters, filters, onSearch, onDeleteAll } = this.props;

        let itemsFilterLabelArray = ["Utilisateur", "Développeur", "Administrateur"];
        let itemsFilterIdArray = ["f-user", "f-dev", "f-admin"];

        let itemsFilter = [
            { value: 0, id: itemsFilterIdArray[0], label: itemsFilterLabelArray[0]},
            { value: 1, id: itemsFilterIdArray[1], label: itemsFilterLabelArray[1] },
            { value: 2, id: itemsFilterIdArray[2], label: itemsFilterLabelArray[2]}
        ]

        return <>
            <div>
                <div className="toolbar">
                    <div className="item create">
                        <Button onClick={() => onChangeContext("create")}>Ajouter un utilisateur</Button>
                    </div>
                    <div className="item filter-search">
                        <Filter ref={this.filter} items={itemsFilter} onGetFilters={onGetFilters} />
                        <Search onSearch={onSearch} />
                        <div className="filters-items-checked">
                            {filters && filters.map(el => {
                                return <div className="item" key={el}>
                                    <div className="role">
                                        <input type="checkbox" name="filters-checked" id={`fcheck-${el}`} data-id={itemsFilterIdArray[el]} value={el} onChange={this.handleFilter}/>
                                        <label htmlFor={`fcheck-${el}`}>
                                            {itemsFilterLabelArray[el]}
                                            <span className="icon-cancel" />
                                        </label>
                                    </div>
                                </div>
                            })}
                        </div>
                    </div>
                </div>

                <div className="items-table">
                    <div className="items items-default items-user">
                        {data && data.map(elem => {
                            return <UserItem {...this.props} elem={elem} key={elem.id}/>
                        })}
                    </div>
                </div>

                <div className="selectors-actions">
                    <div className="item" onClick={onDeleteAll}>
                        <ButtonIcon icon="trash" text="Supprimer la sélection" />
                    </div>
                </div>
            </div>
        </>
    }
}