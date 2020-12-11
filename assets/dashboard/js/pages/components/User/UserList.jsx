import React, { Component } from 'react';

import { Button }    from "@dashboardComponents/Tools/Button";
import {UserItems}   from "./UserItems";

export class UserList extends Component {
    render () {
        const { onChangeContext } = this.props;

        return <>
            <div>
                <div className="toolbar">
                    <div className="item create">
                        <Button onClick={() => onChangeContext("create")}>Ajouter un utilisateur</Button>
                    </div>
                </div>
                <div className="items-table">
                    <UserItems {...this.props} />
                </div>
            </div>
        </>
    }
}