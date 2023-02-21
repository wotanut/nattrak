@extends('_layouts.main')
@section('page')
    <div class="uk-container uk-padding uk-padding-remove-left uk-padding-remove-right">
        <a href="{{ route('pilots.rcl.index') }}"><i class="fas fa-angle-left"></i> Back</a>
        <div class="uk-flex uk-flex-row uk-flex-between">
            <h1 class="uk-text-bold uk-text-primary" id="start">Request oceanic clearance</h1>
        </div>
        @if ($errors->any())
            <div class="uk-alert uk-alert-danger" role="alert">
                <b>Some input was incorrect.</b>
                <ul>
                    @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if ($isConcorde)
            <div class="uk-alert uk-alert-primary" role="alert">
                Concorde aircraft type detected.
            </div>
        @endif
        <form class="uk-form-stacked" action="{{ route('pilots.rcl.store') }}" method="post">
            @csrf
            <h4>Flight information</h4>
            <div class="uk-grid-small" uk-grid>
                <div class="uk-width-1-2@m">
                    <label for="callsign" class="uk-form-label">Callsign</label>
                    <div class="uk-form-controls">
                        <input maxlength="7" required type="text" class="uk-input" name="callsign" id="callsign" placeholder="Enter callsign" value="{{ $callsign ?? old('callsign') }}" onblur="this.value = this.value.toUpperCase()">
                        @if ($callsign)
                            <small class="uk-text-meta">Your callsign was automatically collected. You may change the callsign if it is incorrect.</small>
                        @endif
                    </div>
                </div>
                <div class="uk-width-1-2@m">
                    <label for="destination" class="uk-form-label">Destination ICAO</label>
                    <div class="uk-form-controls">
                        <input required type="text" class="uk-input" name="destination" id="destination" placeholder="Enter destination ICAO (e.g. EGLL)" maxlength="4" value="{{ $arrival_icao ?? old('destination') }}" onblur="this.value = this.value.toUpperCase()">
                        @if (!$arrival_icao)
                            <small class="uk-text-meta">Your destination was automatically collected. You may change the destination if it is incorrect.</small>
                        @endif
                    </div>
                </div>
                <div class="uk-width-1-2@m">
                    <label for="flight_level" class="uk-form-label">Requested {{ $isConcorde ? 'lower block' : '' }} flight level</label>
                    <div class="uk-form-controls">
                        <input required type="text" class="uk-input" name="flight_level" id="flight_level" placeholder="e.g. 310" maxlength="3" value="{{ $flight_level ?? old('flight_level') }}">
                        @if (config('app.ctp_info_enabled'))
                            <small class="uk-text-meta"><b>Ensure you enter your assigned oceanic flight level as per your booking!</b></small>
                        @endif
                        @if ($flight_level)
                            <small class="uk-text-meta">Your requested flight level (the altitude on your flight plan) was automatically collected. You may change the flight level if it is incorrect.</small>
                        @endif
                    </div>
                </div>
                @if ($isConcorde)
                    <div class="uk-width-1-2@m">
                        <label for="flight_level" class="uk-form-label">Requested upper block flight level</label>
                        <div class="uk-form-controls">
                            <input required type="text" class="uk-input" name="upper_flight_level" id="upper_flight_level" placeholder="e.g. 310" maxlength="3" value="{{ old('upper_flight_level') }}">
                        </div>
                    </div>
                @else
                    <div class="uk-width-1-2@m">
                        <label for="max_flight_level" class="uk-form-label">Maximum flight level</label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" name="max_flight_level" id="max_flight_level" placeholder="e.g. 390" maxlength="3" value="{{ old('max_flight_level') }}">
                            @if (config('app.ctp_info_enabled'))
                                <small class="uk-text-meta"><b>Ensure you enter your max flight level as per your booking!</b></small>
                            @endif
                            <small class="uk-text-meta">This is the highest flight level you can accept.</small>
                        </div>
                    </div>
                @endif
                <div class="uk-width-1-2@m">
                    <label for="mach" class="uk-form-label">Requested mach number</label>
                    <div class="uk-form-controls">
                        <input required type="text" class="uk-input" name="mach" id="mach" placeholder="e.g. 080" maxlength="3" value="{{ old('mach') }}">
                        <small class="uk-text-meta">Your requested mach number (don't include the dot at the start)</small>
                    </div>
                </div>
            </div>
            <h4>Route</h4>
            <div class="uk-grid-small" uk-grid>
                <div class="uk-width-2-5@m">
                    <label for="track_id" class="uk-form-label">Requested NAT Track</label>
                    <div class="uk-form-controls">
                        <select class="uk-select" id="track_id" name="track_id">
                            <option value="" selected>None</option>
                            @foreach($tracks as $track)
                                <option value="{{ $track->id }}">{{ $track->identifier }} ({{ $track->last_routeing }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="uk-width-expand@m">
                    <div class="uk-flex uk-flex-middle uk-flex-column">
                        <div class="uk-text-center uk-text-italic">or...</div>
                    </div>
                </div>
                <div class="uk-width-2-5@m">
                    <label for="random_routeing" class="uk-form-label">Requested random routeing</label>
                    <div class="uk-form-controls">
                        <input value="{{ old('random_routeing') }}" type="text" class="uk-input" name="random_routeing" id="random_routeing" placeholder="e.g. GOMUP 59/20 59/30 58/40 56/50 JANJO" onblur="this.value = this.value.toUpperCase()">
                    </div>
                </div>
            </div>
            <h4>Oceanic entry</h4>
            <div class="uk-grid-small" uk-grid>
                <div class="uk-width-1-2@m">
                    <label for="entry_fix" class="uk-form-label">Entry fix</label>
                    <div class="uk-form-controls">
                        <input value="{{ old('entry_fix') }}" required type="text" class="uk-input" name="entry_fix" id="entry_fix" placeholder="e.g. MALOT" maxlength="7" onblur="this.value = this.value.toUpperCase()">
                        <small class="uk-text-meta">The first fix/waypoint in oceanic airspace.</small>
                    </div>
                </div>
                <div class="uk-width-1-2@m">
                    <label for="entry_time" class="uk-form-label">Estimated time of arrival for entry fix</label>
                    <div class="uk-form-controls">
                        <input value="{{ old('entry_time') }}" required type="number" class="uk-input" name="entry_time" id="entry_time" placeholder="e.g. 1350">
                        <small class="uk-text-meta">You can find this in your FMC, providing your simulator is set to real time.</small>
                    </div>
                </div>
            </div>
            <h4>Metadata</h4>
            <div class="uk-grid-small" uk-grid>
                <div class="uk-width-1-2@m">
                    <label for="tmi" class="uk-form-label">Current TMI (available at top of page)</label>
                    <div class="uk-form-controls">
                        <input type="text" class="uk-input" value="{{ old('tmi') }}" required name="tmi" id="tmi" placeholder="e.g. 090" maxlength="4">
                    </div>
                </div>
                <div class="uk-width-1-2@m">
                    <label for="free_text" class="uk-form-label">Free text</label>
                    <div class="uk-form-controls">
                        <input type="text" class="uk-input" value="{{ old('free_text') }}" name="free_text" id="free_text">
                    </div>
                </div>
                <div class="uk-form-controls">
                    <button type="submit" class="uk-button uk-button-primary">Submit Oceanic Clearance Request</button>
                </div>
            </div>
            @if ($isConcorde)
                <input type="hidden" name="is_concorde" value="1">
            @else
                <input type="hidden" name="is_concorde" value="0">
            @endif
            <button type="submit" class="btn btn-primary">Submit Oceanic Clearance Request</button>
        </form>
    </div>
@endsection
