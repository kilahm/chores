<?hh // strict

namespace kilahm\chores\model;

type FieldsAndValues = shape(
    'field list' => string,
    'value list' => string,
    'params' => Map<arraykey, string>,
);

abstract class QueryBuilder
{
    public static function mapToFieldsAndValues(Map<string, string> $data) : FieldsAndValues
    {
        $fields = $data->keys();

        return shape(
            'field list' => implode(', ', $fields),
            'value list' => implode(', ', $fields->map($f ==> ':' . $f)),
            'params' => self::mapToParams($data),
        );
    }

    public static function mapToUpdateList(Map<string, string> $data) : (string, Map<arraykey, string>)
    {
        return tuple(
            implode(', ',  $data->mapWithKey(($field, $value) ==> "\"$field\" = :$field")),
            self::mapToParams($data),
        );
    }

    private static function mapToParams(Map<string, string> $data) : Map<arraykey, string>
    {
        $params = Map{};
        foreach($data as $field => $value) {
            $params->set(':' . $field, $value);
        }
        return $params;
    }
}
