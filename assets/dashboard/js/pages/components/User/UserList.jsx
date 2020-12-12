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

        this.handleChange = this.handleChange.bind(this);
    }

    handleChange = (e) => {
        this.setState({ [e.currentTarget.name]: e.currentTarget.value });
    }

    render () {
        const { onChangeContext, onGetFilters } = this.props;
        const { search } = this.state;

        let itemsFilter = [
            { value: 1, id: "f-superadmin", label: "Super administrateur" },
            { value: 2, id: "f-admin",      label: "Administrateur"},
            { value: 0, id: "f-user",       label: "Utilistateur"}
        ]

        return <>
            <div>
                <div className="toolbar">
                    <div className="item create">
                        <Button onClick={() => onChangeContext("create")}>Ajouter un utilisateur</Button>
                    </div>
                    <div className="item filter-search">
                        <Filter items={itemsFilter} onGetFilters={onGetFilters} />
                        <div className="search">
                            <input type="search" name="search" id="search" value={search} placeholder="Recherche..." onChange={this.handleChange} />
                            <span className="icon-search" />
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