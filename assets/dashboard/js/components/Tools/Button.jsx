import React from "react";

export function ButtonIcon(props){
    const { icon, children, text, onClick } = props;

    return <button className="btn-icon" onClick={onClick}>
        <span className={`icon-${icon}`} />
        {text && <span>{text}</span>}
        {children && <span className="tooltip">{children}</span>}
    </button>
}

export function Button(props){
    const { icon, type="primary", isSubmit=false, children, onClick } = props;

    return <button className={`btn btn-${type}`} type={isSubmit ? "submit" : ""} onClick={onClick}>
        {icon && <span className={`icon-${icon}`} />}
        {children}
    </button>
}