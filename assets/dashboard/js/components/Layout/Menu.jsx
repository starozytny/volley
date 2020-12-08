import React, { Component } from 'react';

export class Menu extends Component {
    constructor(props) {
        super();
    }

    render () {
        const { menu } = this.props

        return <>
            <div className="nav-mobile">
                <span className="icon-menu"/>
            </div>
            <div className="nav-body">
                <div className="menu">
                    {JSON.parse(menu).map(el => {
                        return <div key={el.name} className="item">
                            <a href={el.path}>
                                <span className={`icon-${el.icon}`} />
                                <span>{el.label}</span>
                            </a>
                        </div>
                    })}
                </div>
            </div>
        </>
    }
}