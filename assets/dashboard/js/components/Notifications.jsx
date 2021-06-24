import React, { Component } from "react";

export class Notifications extends Component{
    constructor(props) {
        super();

        this.state = {
            open: false,
        }

        this.handleOpen = this.handleOpen.bind(this);
    }

    handleOpen = () => { this.setState({ open: !this.state.open }) }

    render() {
        const { open } = this.state;

        return <div className={"notif-container" + (open ? " active" : "")}>
            <div className="btn-notif" onClick={this.handleOpen}>
                <span className="icon-notification" />
                <span className="number">5</span>
            </div>
            <div className="notif-card">
                <div className="notif-cancel">
                    <span>Notifications</span>
                    <span className="icon-cancel" onClick={this.handleOpen} />
                </div>
                <div className="notif-body">
                    <div className="item">
                        <div className="item-content">
                            <div className="item-icon">
                                <span className="icon-user" />
                            </div>
                            <div className="item-infos">
                                <div className="title">Nouveau utilisateur</div>
                                <div className="createdAt">Il y a 5 minutes</div>
                            </div>
                        </div>
                        <div className="item-actions">
                            <span className="icon-trash" />
                        </div>
                    </div>
                    <div className="item">
                        <div className="item-content">
                            <div className="item-icon">
                                <span className="icon-user" />
                            </div>
                            <div className="item-infos">
                                <div className="title">Nouveau utilisateur</div>
                                <div className="createdAt">Il y a 5 minutes</div>
                            </div>
                        </div>
                        <div className="item-actions">
                            <span className="icon-trash" />
                        </div>
                    </div>
                </div>
                <div className="notif-all">
                    Voir toutes les notifications
                </div>
            </div>
        </div>
    }
}