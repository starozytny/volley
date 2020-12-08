import React, { Component } from 'react';

export class Menu extends Component {
    constructor(props) {
        super();

        this.state = {
            isOpened: false
        }

        this.handleOpenMenu = this.handleOpenMenu.bind();
    }

    handleOpenMenu = () => {
        this.setState(prevState => {
            return { isOpened: !prevState.isOpened }
        })
    }

    render () {
        const { menu, menuBottom } = this.props
        const { isOpened } = this.state

        return <>
            <div className="nav-mobile" onClick={this.handleOpenMenu}>
                <span className={`icon-${ isOpened ? "cancel" : "menu" }`} />
            </div>
            <div className={`nav-body ${isOpened}`}>
                <div className="items">
                    { <MenuItem menu={menu} /> }
                </div>
                <div className="items">
                    { <MenuItem menu={menuBottom} /> }
                </div>
            </div>
        </>
    }
}

function MenuItem (props){
    const { menu } = props

    return (
        JSON.parse(menu).map(el => {
            return <div key={el.name} className="item">
                <a href={el.path}>
                    <span className={`icon-${el.icon}`} />
                    <span>{el.label}</span>
                </a>
            </div>
        })
    )
}