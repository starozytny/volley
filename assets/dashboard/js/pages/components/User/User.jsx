import React, { Component } from 'react';

import { Toolbar } from "../../../components/Layout/Toolbar";


export class User extends Component {
    render () {
        return <>
            <Toolbar />
            <div className="main-content">
                Hello world
            </div>
        </>
    }
}