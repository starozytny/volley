import React, { Component } from "react";

import "leaflet/dist/leaflet.css";
import L from "leaflet/dist/leaflet";

export class StyleguideMaps extends Component{
    componentDidMount = () => {
        let mymap = L.map('mapid').setView([51.505, -0.09], 13);
        L.tileLayer('https://b.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Tuiles de fond <a href="https://www.openstreetmap.org/copyright">OSM.org</a>',
            maxZoom: 18,
        }).addTo(mymap);

        let leafletIcon = L.icon({
            iconUrl: '../../maps/images/marker-icon.png',
            shadowUrl: '../../maps/images/marker-shadow.png',
            iconSize:     [25, 41], // size of the icon
            shadowSize:   [41, 41], // size of the shadow
            iconAnchor:   [9, 40], // point of the icon which will correspond to marker's location 38 95
            shadowAnchor: [9, 40],  // the same for the shadow 50 61
            popupAnchor:  [4, -35] // point from which the popup should open relative to the iconAnchor
        })

        let marker = L.marker([51.5, -0.09], {icon: leafletIcon}).addTo(mymap);
        let circle = L.circle([51.508, -0.11], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 500
        }).addTo(mymap);
        let polygon = L.polygon([
            [51.509, -0.08],
            [51.503, -0.06],
            [51.51, -0.047]
        ]).addTo(mymap);

        marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();
    }

    render () {
        const divStyle = {
            height: '50vh'
        };

        return (
            <section>
                <h2>OpenstreetMaps - Leaflet</h2>
                <div className="maps-items">
                    <div id="mapid" style={divStyle} />
                </div>
            </section>
        )
    }

}