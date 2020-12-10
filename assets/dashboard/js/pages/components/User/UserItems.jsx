import React from 'react';

export function UserItems (props) {
    const { data } = props

    return <div className="items">
        {data.map(elem => {
            return <div key={elem.id}>
                <div className="username">{elem.username}</div>
            </div>
        })}
    </div>
}