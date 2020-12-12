import React, { Component } from 'react';

import { Alert } from "@dashboardComponents/Tools/Alert";

export class Filter extends Component {
    constructor() {
        super();

        this.state = {
            filters: []
        }

        this.handleChange = this.handleChange.bind(this);
    }

    // from fo uncheck via toolbar filter checked
    handleChange = (e, from=false) => {
        const {filters} = this.state;

        let value = parseInt(e.currentTarget.value);
        let newFilter;
        if(!from){
            newFilter = (e.currentTarget.checked) ? [...filters, ...[value]] : filters.filter(v => v !== value)
        }else{
            newFilter = filters.filter(v => v !== value)
            document.getElementById(e.currentTarget.dataset.id).click();
        }

        this.setState({filters: newFilter});

        this.props.onGetFilters(newFilter);
    }

    render () {
        const { items } = this.props;

        return <div className="filter">
            <div className="dropdown">
                <div className="dropdown-btn">
                    <span>Filtre</span>
                    <span className="icon-filter" />
                </div>
                <div className="dropdown-items">
                    {items.map((el, index) => {
                        return <div className="item" key={index}>
                            <input type="checkbox" name="filters" id={el.id} value={el.value} onChange={this.handleChange}/>
                            <label htmlFor={el.id}>{el.label}</label>
                        </div>
                    })}
                </div>
            </div>
        </div>
    }
}