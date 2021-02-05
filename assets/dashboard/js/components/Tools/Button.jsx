import React from "react";

export function ButtonIcon(props){
    const { icon, children, text, onClick, element="button", target="_self" } = props;

    if(element === "button"){
        return <button className="btn-icon" onClick={onClick}>
            <span className={`icon-${icon}`} />
            {text && <span>{text}</span>}
            {children && <span className="tooltip">{children}</span>}
        </button>
    }else{
        return <a className="btn-icon" target={target} href={onClick}>
            <span className={`icon-${icon}`} />
            {text && <span>{text}</span>}
            {children && <span className="tooltip">{children}</span>}
        </a>
    }
}

export function Button(props){
    const { icon, type="primary", isSubmit=false, children, onClick, element="button", target="_self" } = props;

    if(element === "button"){
        return <button className={`btn btn-${type}`} type={isSubmit ? "submit" : ""} onClick={onClick}>
            {icon && <span className={`icon-${icon}`} />}
            {children}
        </button>
    }else{
        return <a className={`btn btn-${type}`} target={target} href={onClick}>
            {icon && <span className={`icon-${icon}`} />}
            {children}
        </a>
    }
}