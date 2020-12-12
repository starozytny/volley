import React, { Component } from 'react';

import { Alert } from "@dashboardComponents/Tools/Alert";

export class Selector extends Component {
    constructor() {
        super();

        this.handleChange = this.handleChange.bind(this);
    }

    componentDidMount() {
        const { id, selectors } = this.props;

        let isChecked = false;
        if(selectors.length !== 0){
            selectors.map(el => {
                if(el === id){
                    isChecked = true;
                }
            })
        }

        document.getElementById('item-selector-' + id).checked = isChecked;
    }


    // from fo uncheck via toolbar filter checked
    handleChange = (e) => {
        let value = parseInt(e.currentTarget.value);
        let isChecked = !!(e.currentTarget.checked)

        this.props.onSelectors(value, isChecked);
    }

    render () {
        const { id, selectors } = this.props;

        let isCheck;
        if(selectors.length === 0){
            isCheck = false
        }else{
            selectors.map(el => {
                if(el === id){
                    isCheck = true
                }
            })
        }

        return <div className="selector">
            <input type="checkbox" name="item-selector" id={`item-selector-${id}`} value={id} checked={isCheck} onChange={this.handleChange}/>
            <label htmlFor={`item-selector-${id}`} className={`item-selector ${isCheck}`}/>
        </div>
    }
}