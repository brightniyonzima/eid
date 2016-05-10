
        <tr>
            <td>{!! $this_sample->pos !!} </td>
            <td>{!! $this_sample->infant_id !!} </td>
            <td>
                {!! Form::select($this_sample->pos, $possible_results, $this_sample->result) !!}
            </td>
        </tr>
