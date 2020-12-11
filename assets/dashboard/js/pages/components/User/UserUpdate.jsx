import React, { Component } from 'react';

import { Button }    from "@dashboardComponents/Tools/Button";

export class UserUpdate extends Component {
    render () {
        const { onChangeContext, element } = this.props;

        return <>
            <div>
                <div className="toolbar">
                    <div className="item">
                        <Button icon="left-arrow" type="default" onClick={() => onChangeContext("list")}>Retour à la liste</Button>
                    </div>
                </div>
                <h2>Modifier {element.username}</h2>
            </div>
        </>
    }
}