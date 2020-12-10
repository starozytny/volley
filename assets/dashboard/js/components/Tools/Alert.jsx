import React from "react";

export function Alert(props){
    const { type, title, children } = props;

    let icon;
    switch (type){
        case "info":
            icon = "information";
            break;
        default:
            icon = "warning";
            break;
    }

    return <div className="alert alert-danger">
        <span className={`icon-${icon}`} />
        <p>
            {title && <span className="title">{title}</span>}
            {children}
        </p>
    </div>
}