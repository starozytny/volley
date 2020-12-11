import React from "react";

export function Alert(props){
    const { type, title, children } = props;

    let icon, alert;
    switch (type){
        case "warning":
            alert = "warning";
            icon = "warning";
            break;
        case "info":
            alert = "primary";
            icon = "information";
            break;
        default:
            alert = "danger";
            icon = "warning";
            break;
    }

    return <div className={`alert alert-${alert}`}>
        <span className={`icon-${icon}`} />
        <p>
            {title && <span className="title">{title}</span>}
            {children}
        </p>
    </div>
}