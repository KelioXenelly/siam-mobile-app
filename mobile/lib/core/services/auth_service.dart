import 'package:dio/dio.dart';
import 'package:mobile/core/network/dio_client.dart';

class AuthService {
  final Dio _dio = DioClient.dio;

  Future<Response> login(String identifier, String password) async {
    return await _dio.post(
      '/login',
      data: {
        'identifier': identifier,
        'password': password,
      },
    );
  }
}