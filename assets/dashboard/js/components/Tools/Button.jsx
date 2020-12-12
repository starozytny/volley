import React from "react";

export function ButtonIcon(props){
    const { icon, children, text, onClick } = props;

    return <div className="btn-icon" onClick={onClick}>
        <span className={`icon-${icon}`} />
        {text && <span>{text}</span>}
        {children && <span className="tooltip">{children}</span>}
    </div>
}

export function Button(props){
    const { icon, type="primary", children, onClick } = props;

    return <div className={`btn btn-${type}`} onClick={onClick}>
        {icon && <span className={`icon-${icon}`} />}
        {children}
    </div>
}