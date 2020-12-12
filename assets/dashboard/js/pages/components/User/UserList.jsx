import React, { Component } from 'react';

import { Button }    from "@dashboardComponents/Tools/Button";
import { Input }    from "@dashboardComponents/Tools/Fields";

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
        const { onChangeContext } = this.props;
        const { search } = this.state;

        return <>
            <div>
                <div className="toolbar">
                    <div className="item create">
                        <Button onClick={() => onChangeContext("create")}>Ajouter un utilisateur</Button>
                    </div>
                    <div className="item filter-search">
                        <div className="filter">
                            <div className="dropdown">
                                <div className="dropdown-btn">
                                    <span>Filtre</span>
                                    <span className="icon-filter" />
                                </div>
                                <div className="dropdown-items">
                                    <div className="item">
                                        <input type="checkbox" name="filter-user" id="f-superadmin" value="1" onChange={this.handleChange}/>
                                        <label htmlFor="f-superadmin">Super administrateur</label>
                                    </div>
                                    <div className="item">
                                        <input type="checkbox" name="filter-user" id="f-admin" value="2" onChange={this.handleChange}/>
                                        <label htmlFor="f-admin">Administrateur</label>
                                    </div>
                                    <div className="item">
                                        <input type="checkbox" name="filter-user" id="f-user" value="0" onChange={this.handleChange}/>
                                        <label htmlFor="f-user">Utilisateur</label>
                                    </div>
                                </div>
                            </div>
                        </div>
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