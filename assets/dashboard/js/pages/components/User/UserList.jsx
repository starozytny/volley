import React, { Component } from 'react';

import { Button }    from "@dashboardComponents/Tools/Button";
import { Input }    from "@dashboardComponents/Tools/Fields";

import { Filter }    from "@dashboardComponents/Layout/Filter";

import {UserItems}   from "./UserItems";

export class UserList extends Component {
    constructor(props) {
        super(props);

        this.state = {
            search: ""
        }

        this.filter = React.createRef();

        this.handleChange = this.handleChange.bind(this);
        this.handleFilter = this.handleFilter.bind(this);
    }

    handleChange = (e) => {
        this.setState({ [e.currentTarget.name]: e.currentTarget.value });
    }

    handleFilter = (e) => {
        this.filter.current.handleChange(e, true);
    }

    render () {
        const { onChangeContext, onGetFilters, filters } = this.props;
        const { search } = this.state;

        let itemsFilterLabelArray = ["Utilisateur", "Super administrateur", "Administrateur"];
        let itemsFilterIdArray = ["f-user", "f-superadmin", "f-admin"];

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
                        <div className="search">
                            <input type="search" name="search" id="search" value={search} placeholder="Recherche..." onChange={this.handleChange} />
                            <span className="icon-search" />
                        </div>

                        <div className="filters-items-checked">
                            {filters && filters.map(el => {
                                return <div className="item" key={el}>
                                    <div className="role">
                                        <input type="checkbox" name="filters-checked" id={`fcheck-${el}`} data-id={itemsFilterIdArray[el]} value={el} onChange={this.handleFilter}/>
                                        <label htmlFor={`fcheck-${el}`}>{itemsFilterLabelArray[el]}</label>
                                        <span className="icon-cancel" />
                                    </div>
                                </div>
                            })}
                        </div>
                    </div>
                </div>

                <div className="items-table">
                    <UserItems {...this.props} />
                </div>
            </div>
        </>
    }
}