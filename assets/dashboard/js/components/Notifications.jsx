import React, { Component } from "react";

export class Notifications extends Component{
    render() {
        return <div className="notif-container">
            <div className="btn-notif">
                <span className="icon-notification" />
                <span className="number">5</span>
            </div>
            <div className="notif-card">
                <div className="notif-cancel">
                    <span>Notifications</span>
                    <span className="icon-cancel" />
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